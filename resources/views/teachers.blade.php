@extends('layouts.app')

@section('title')
    Teachers
@endsection

@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Teachers</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item active">Teachers</li>
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
                                Teachers Table
                            </h6>
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <div class="col-lg d-flex justify-content-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddTeacher">
                                    Add Teacher <i class="fa-solid fa-user-plus"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    @if (!($teachers->isEmpty()))
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">E-mail</th>
                                    @if (Auth::user()->role_id == 1) {{--? displaying just for admins --}}
                                        <th scope="col">Status</th>
                                    @endif
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $x = 0 @endphp
                                @foreach ($teachers as $teacher)
                                    @if ($teacher->status != 2) {{--? displaying not waiting teachers --}}
                                        @php $x++ @endphp
                                        <tr id="teacher_{{ $teacher-> id }}">
                                            <th scope="row">{{ $x }}</th>
                                            <td class="teacher_name">{{ $teacher-> name }}</td>
                                            <td class="teacher_username">{{ $teacher-> username}}</td>
                                            <td class="teacher_email">{{ $teacher-> email }}</td>
                                            @if (Auth::user()->role_id == 1) {{--? displaying just for admins --}}
                                                <td>
                                                    @if ($teacher-> status == 1)
                                                        <span class="badge text-bg-danger">Not-Active</span>
                                                    @elseif ($teacher-> status == 3)
                                                        <span class="badge text-bg-success">Active</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                {{--? showing delete btn for just admins --}}
                                                @if (Auth::user()->role_id == 1)

                                                    {{--Todos: Delete teacher btn  --}}
                                                    {{--! at href we disabled the refresh from btn, onclick-> goes forward deletepost function  --}}
                                                    <a class="btn btn-danger" title="Delete" role="button" href="javascript:void(0)" onclick="deleteteacher({{ $teacher-> id }})">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                @endif
                                                {{--? showing edit btn for just teacher and can't access on any another teacher's data --}}
                                                @if (Auth::user()->role_id == 2 && Auth::user()->id == $teacher-> id)

                                                    {{--Todo: Edit teacher btn --}}
                                                    <button type="button" class="btn btn-primary edit_btn" data-bs-toggle="modal" title="Edit"
                                                        data-bs-target="#EditTeacher" data-edit_route="{{route('teacher.edit_teacher',Auth::user()->id)}}">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>

                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{--? pagination bar --}}
                        {!! $teachers->links('pagination::bootstrap-5') !!}

                    @else
                        <h3 class="text-danger text-center">No available data </h3>
                    @endif

                </div>
            </div>

            {{--? add teacher modal just viewed for admins --}}
            @if (Auth::user()->role_id == 1)

                {{--Todos: add teacher modal --}}
                <div class="modal fade" id="AddTeacher" tabindex="-1" aria-labelledby="AddTeacher" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Teacher</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('teacher.add_teacher') }}" method="post">
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
                                        <label for="E-mail">E-mail</label>
                                        <input type="email" class="form-control @error ('email') is-invalid @enderror" id="E-mail" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="Username">Username</label>
                                        <input type="text" class="form-control @error ('username') is-invalid @enderror" id="Username" name="username" value="{{ old('username') }}">
                                        @error('username')
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

            {{--? showing modals if user is teacher & he edit his data --}}
            @if (!empty($teachers))
                @if (Auth::user()->role_id == 2 && Auth::user()->id == $teacher->id)

                    {{--Todos: edit teacher modal --}}
                    <div class="modal fade" id="EditTeacher" tabindex="-1" aria-labelledby="EditTeacher" aria-hidden="true">
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
                                            <input type="hidden" id="teacher_id" value="">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>

                                        <div class="mb-3">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>


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


    {{--Todo: edit user ajax function --}}
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
                    $("#teacher_id").val(data.id);
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                    $("#username").val(data.username);

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
                var teacherID = $('#teacher_id').val();

                // Construct the update route dynamically
                var updateRoute = '{{ route('teacher.update_teacher','') }}' + '/' + teacherID;

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

                        // Update the teacher_name
                        var $TeacherName = $('#teacher_' + teacherID + ' .teacher_name');
                        $TeacherName.empty().html(DataBack.teacher.name);

                        // Update the teacher_username
                        var $TeacherUsername = $('#teacher_' + teacherID + ' .teacher_username');
                        $TeacherUsername.empty().html(DataBack.teacher.username);

                        // Update the teacher_email
                        var $TeacherEmail = $('#teacher_' + teacherID + ' .teacher_email');
                        $TeacherEmail.empty().html(DataBack.teacher.email);

                        // Close the modal
                        $('#EditTeacher').modal('hide');
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

        function deleteteacher(id)
        {
            if(confirm("Are you sure to delete this teacher"))
            {
                $.ajax({
                    url:"{{ route('teacher.destroy','') }}" + "/" + id, //destory function
                    type:'DELETE',
                    success:function(result)
                    {
                        $("#"+result['teacherData']).remove();
                    }
                });
            }
        }

    </script>

@endsection
