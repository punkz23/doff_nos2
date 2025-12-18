@foreach($terms as $key=>$row)
    <h1>Terms and condition of {{$titles[$key]}}</h1>
    {!! $row->content !!}
@endforeach



