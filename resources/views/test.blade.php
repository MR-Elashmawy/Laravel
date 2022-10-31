<!-- when see any file end with """"".blade.php"""""" this mean three features:
1) help us to write clean code simple
2) saved me from security valnarabilities
3)
-->
<h2> {{$name}}</h2>
<h2>{{$age}}</h2>
@foreach ($sub as $Subjects)
    <h2>{{$Subjects}}</h2>
@endforeach
