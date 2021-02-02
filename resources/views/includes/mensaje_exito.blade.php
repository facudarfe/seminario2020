<div class="row">
    <div class="col-12">
        @if (session('exito'))
        <div class="alert alert-success alert-dismissable fade show">
            <i class="fas fa-check"></i>
            {{session('exito')}}
            <a href="#" class="close" data-dismiss="alert">&times;</a>
        </div>
        @endif
    </div>
</div>