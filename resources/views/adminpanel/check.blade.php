@extends('layouts.master')

@section('content')
       
                		<div class="container">

                    <table class="table table-hover">
                        <thead>
                                <th>S/N</th>
                                <th>Pins</th>
    
                                <th>Used Pin</th>
                                <th> Date created</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($numbers as $number)
                            
                            <tr>
                                <td>{{$loop->iteration}}</td>
                            
                                <td>{{$number->numbers}}</td>
                                <td>status</td>
                                <td>{{$number->created_at->toDateTimeString()}}</td>
                                {{--  <td>{{$number->created_at>}}</td>  --}}
                            </tr> 
                            @endforeach
                            
                        </tbody>

                    </table>
                    {{$numbers->links()}}
		</div>

        <form method="POST" action="/enterpin">
            {{ csrf_field() }}

            <div class="form-group col-lg-4">
                <input  class="form-control" type="text" name="random" id="create"  required>

            </div>

                 <div class="form-group ">
                        <input class="btn btn-success" type="submit" name="submit" value="Submit Form">
                        &nbsp;
                

                    </div>


        </form>
@endsection

