@if(session('messages'))
    <script>
        $.toptip('{{session('messages')}}', 'success');
    </script>
@endif