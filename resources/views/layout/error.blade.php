@if(count($errors))
    <script>
        $.toptip('{{$errors->first()}}', 'error');
    </script>
@endif