@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
        <strong>Whoops! Something went wrong!</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif