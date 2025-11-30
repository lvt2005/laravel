<?php

namespace App\Http\Controllers;

use App\Models\MedicalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMedicalNoteController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        if (!$doctorId) return response()->json(['message' => 'Doctor profile not found'], 404);

        $query = MedicalNote::where('doctor_id', $doctorId)->orderByDesc('visit_date')->orderByDesc('id');

        if ($search = $request->query('q')) {
            $query->where(function ($q2) use ($search) {
                $q2->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        $notes = $query->paginate(10);
        return response()->json($notes);
    }

    public function completedPatients(Request $request)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        if (!$doctorId) return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);

        $search = $request->get('search', '');
        
        // Bao gồm cả lịch COMPLETED và IN_PROGRESS để có thể thêm ghi chú
        $query = \DB::table('appointment_schedules')
            ->select('patient_id', 'patient_name', 'appointment_date', 'user_id', 'status', 'id as appointment_id')
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['COMPLETED', 'IN_PROGRESS'])
            ->whereNotNull('patient_name');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }
        
        $rows = $query->orderBy('appointment_date', 'desc')
            ->orderBy('status', 'asc') // IN_PROGRESS trước
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    public function store(Request $request)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        if (!$doctorId) return response()->json(['message' => 'Doctor profile not found'], 404);

        $data = $request->validate([
            'patient_id' => ['nullable', 'integer'],
            'appointment_id' => ['nullable', 'integer'],
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_phone' => ['nullable', 'string', 'max:255'],
            'clinical_history' => ['required', 'string'],
            'chief_complaint' => ['required', 'string'],
            'physical_examination' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'treatment_plan' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'visit_date' => ['required', 'date'],
            'visit_type' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric'],
            'height' => ['nullable', 'numeric'],
            'blood_pressure_systolic' => ['nullable', 'integer'],
            'blood_pressure_diastolic' => ['nullable', 'integer'],
            'temperature' => ['nullable', 'numeric'],
            'heart_rate' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
        ]);

        $data['doctor_id'] = $doctorId;
        $data['status'] = $data['status'] ?? 'final';

        $note = MedicalNote::create($data);

        return response()->json($note, 201);
    }

    public function show($id)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);
        return response()->json($note);
    }

    public function update(Request $request, $id)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);

        $data = $request->validate([
            'patient_id' => ['nullable', 'integer'],
            'appointment_id' => ['nullable', 'integer'],
            'patient_name' => ['sometimes', 'string', 'max:255'],
            'patient_phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'clinical_history' => ['sometimes', 'string'],
            'chief_complaint' => ['sometimes', 'string'],
            'physical_examination' => ['sometimes', 'string'],
            'diagnosis' => ['sometimes', 'string'],
            'treatment_plan' => ['sometimes', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'visit_date' => ['sometimes', 'date'],
            'visit_type' => ['sometimes', 'string', 'max:255'],
            'weight' => ['sometimes', 'nullable', 'numeric'],
            'height' => ['sometimes', 'nullable', 'numeric'],
            'blood_pressure_systolic' => ['sometimes', 'nullable', 'integer'],
            'blood_pressure_diastolic' => ['sometimes', 'nullable', 'integer'],
            'temperature' => ['sometimes', 'nullable', 'numeric'],
            'heart_rate' => ['sometimes', 'nullable', 'integer'],
            'status' => ['sometimes', 'string', 'max:255'],
        ]);

        $note->fill($data);
        $note->save();

        return response()->json($note);
    }

    public function destroy($id)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);
        $note->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
