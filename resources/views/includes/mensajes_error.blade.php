<div class="row">
    <div class="col-12">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissable fade show">
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
            <a href="#" class="close" data-dismiss="alert">&times;</a>
        </div>
        @endif 
    </div>
</div>