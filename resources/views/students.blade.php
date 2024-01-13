@extends('layouts.app')

@section('title')
    Students
@endsection

@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Students</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item active">Students</li>
            </ol>

            <div class="card mb-4">
                <div class="card-body">

                    {{--Todos: showing error messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-lg-1 mt-2">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{--Todos: showing add (session) message --}}
                    @elseif (session()->has('Add'))
                        <div class="alert alert-success" role="alert">
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                        <strong>{{ session()->get('Add') }}</strong>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="row">
                        <div class="col-lg d-flex @if(Auth::user()->role_id == 1) justify-content-end @else justify-content-center @endif ">
                            <h6>
                                Students Table
                            </h6>
                        </div>

                        @if (Auth::user()->role_id == 1)
                            <div class="col-lg d-flex justify-content-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddStudent">
                                    Add Student <i class="fa-solid fa-user-plus"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    @if (!($students->isEmpty()))
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Reponsible Teacher</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $x = 0 @endphp
                                @foreach ($students as $student)
                                    @php $x++ @endphp
                                    <tr id="student_{{ $student->id }}">
                                        <th scope="row">{{ $x }}</th>
                                        <td class="student_name">{{ $student-> name }}</td>
                                        <td class="student_age">{{ $student-> age }}</td>
                                        <td class="student_teacher">{{ $student->teacher-> name }}</td>
                                        <td>

                                            {{--Todos: Delete teacher btn  --}}
                                            {{--! at href we disabled the refresh from btn, onclick-> goes forward deletepost function  --}}
                                            <a class="btn btn-danger" title="Delete" role="button" href="javascript:void(0)" onclick="deletestudent({{ $student-> id }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>

                                            {{--Todo: Edit student btn --}}
                                            <button type="button" class="btn btn-primary edit_btn" data-bs-toggle="modal" title="Edit"
                                                data-bs-target="#EditStudent" data-edit_route="{{route('student.edit',$student-> id)}}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{--? pagination bar --}}
                        {!! $students->links('pagination::bootstrap-5') !!}
                    @else
                        <h3 class="text-danger text-center">No available data </h3>
                    @endif

                </div>
            </div>

            {{--? add student modal just viewed for admins --}}
            @if (Auth::user()->role_id == 1)

                {{--Todos: add student modal --}}
                <div class="modal fade" id="AddStudent" tabindex="-1" aria-labelledby="AddStudent" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Student</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('student.store') }}" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="Name">Name</label>
                                        <input type="text" class="form-control @error ('name') is-invalid @enderror" id="Name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Age">age</label>
                                        <input type="text" class="form-control @error ('age') is-invalid @enderror" id="Age" name="age" value="{{ old('age') }}">
                                        @error('age')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Teacher_id">Responsible Teacher</label>
                                        <select name="teacher_id" id="Teacher_id" class="form-control @error ('teacher_id') is-invalid @enderror">
                                            <option disabled selected value>----- Choose responsible teacher -----</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher-> id }}">{{ $teacher-> name }}</option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Add</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

            @if (!empty($students))
                {{--Todos: edit student modal --}}
                <div class="modal fade" id="EditStudent" tabindex="-1" aria-labelledby="EditStudent" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Teacher</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="updateform" method="post">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                        <input type="hidden" id="stu_id" value="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="age">Age</label>
                                        <input type="text" class="form-control" id="age" name="age">
                                    </div>

                                    {{--? displaying  --}}
                                    @if (Auth::user()->role_id == 1)
                                        <div class="mb-3">
                                            <label for="Teacher_id">Responsible Teacher</label>
                                            <select name="teacher_id" id="Teacher_id" class="form-control">
                                                <option disabled selected value>----- Choose responsible teacher -----</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher-> id }}">{{ $teacher-> name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary"  id="submitUpdate">Save changes</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>
@endsection

@section('js')

    <script>
        $.ajaxSetup({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{--Todo: Edit ajax function --}}
    <script type="text/javascript">

        $(document).on("click",".edit_btn",function() //when we click on button with class name (edit_btn)
        {

            var editRoute = $(this).attr("data-edit_route"); //put the value of edit_route in var

            console.log("Clicked on edit button, route:", editRoute);

            $.ajax({
                type:"get",
                url:editRoute,
                dataType:"json",
                success: function (data) {

                    //show data in console with as json form the controller
                    console.log("AJAX success, data:", data);

                    // showing data
                    $("#stu_id").val(data.id);
                    $("#name").val(data.name);
                    $("#age").val(data.age);

                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },

            });
        });

    </script>

    {{--Todos: Update ajax function --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submitUpdate').click(function() {

                // Serialize the form data
                var formData = $('#updateform').serialize();

                // Get the post ID from the hidden input field
                var studentID = $('#stu_id').val();

                // Construct the update route dynamically
                var updateRoute = '{{ route('student.update','') }}' + '/' + studentID;

                console.log("Clicked on update button, route:", updateRoute);

                // Make the AJAX request
                $.ajax({
                    url: updateRoute,
                    method: 'put', // Use PUT for update
                    data: formData,
                    dataType: 'json',
                    success: function(DataBack) {
                        // Handle success DataBack
                        console.log("AJAX success, data:", DataBack);

                        // Update the student_name
                        var $StudentName = $('#student_' + studentID + ' .student_name');
                        $StudentName.empty().html(DataBack.student.name);

                        // Update the student_age
                        var $StudentAge = $('#student_' + studentID + ' .student_age');
                        $StudentAge.empty().html(DataBack.student.age);

                        // Update the student_responsible-teacher
                        var $Student_Teacher = $('#student_' + studentID + ' .student_teacher');
                        $Student_Teacher.empty().html(DataBack.teacher_name);


                        // Close the modal
                        $('#EditStudent').modal('hide');
                        // Reset the form
                        $('#updateform')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Handle error DataBack
                        console.error("AJAX Error:", status, error);
                    }
                });
            });
        });

    </script>

    {{--Todos: Delete ajax function --}}
    <script type="text/javascript">

        function deletestudent(id)
        {
            if(confirm("Are you sure to delete this student"))
            {
                $.ajax({
                    // url:"{{ route('student.destroy','') }}" + "/" + id, //destory function
                    url:"/student/" + id, //destory function
                    type:'DELETE',
                    success:function(result)
                    {
                        $("#"+result['studentData']).remove();
                    }
                });
            }
        }

    </script>

@endsection
