<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Exports\StudentsExport;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request as HttpRequest;

class StudentController extends Controller
{
    public function index(HttpRequest $request)
    {
        $paginate = request('paginate');

        if(!empty($request->header('name'))){
            $students = Student::where('name', 'like', '%' . $request->header('name') . '%')->paginate($paginate);
        }
        elseif(!empty($request->header('email'))){
            $students = Student::where('email', 'like', '%' . $request->header('email') . '%')->paginate($paginate);
        }
        elseif(!empty($request->header('address'))){
            $students = Student::where('address', 'like', '%' . $request->header('address') . '%')->paginate($paginate);
        }
        elseif(!empty($request->header('phoneNo'))){
            $students = Student::where('phone_number', 'like', '%' . $request->header('phoneNo') . '%')->paginate($paginate);
        }
        elseif (isset($paginate)) {
            $students = Student::studentsQuery()->paginate($paginate);
        }

        else {
            $students = Student::studentsQuery()->get();
        }


        return StudentResource::collection($students);
    }


    public function destroy(Student $student)
    {
        $student->delete();
        return response()->noContent();
    }

    public function massDestroy($students)
    {
        $studentsArray = explode(',', $students);
        Student::whereKey($studentsArray)->delete();
        return response()->noContent();
    }

    public function export($students)
    {
        $studentsArray = explode(',', $students);
        return (new StudentsExport($studentsArray))->download('students.xlsx');
    }
}
