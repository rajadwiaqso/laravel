@extends('layout')
@section('konten')
    <h1>Hello Admin</h1>
    <div class="container my-5">
        <div
            class="table-responsive"
        >
            <table
                class="table table-primary"
            >
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">img</th>
                        <th scope="col">message</th>
                        <th scope="col">agreement</th>
                    </tr>
                </thead>
                <tbody>
                    
                        @foreach ($forms as $form)
                        <tr class="">
                        <td scope="row">{{$form->name}}</td>
                        <td>{{$form->email}}</td>
                        <td><img src="{{$form->img}}" alt="{{$form->img}}"></td>
                        <td>{{$form->message}}</td>
                        <td>
                            <form action="{{route('form.accept', $form->id)}}" method="post">
                                @csrf
                                <button class="btn btn-success">Accept</button>
                            </form>
                            <form action="{{route('form.decline', $form->id)}}" method="post">
                                @csrf
                                <button class="btn btn-danger">Decline</button>
                            </form>
                        </td>
                    </tr>

                        @endforeach
                  
                </tbody>
            </table>
        </div>
        
    </div>
@endsection