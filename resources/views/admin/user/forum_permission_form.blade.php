
<div class="row justify-content-center">
   <div class="col-sm-10">
        <select class="form-control chosen-select" id="categories" multiple="multiple" name="categories[]">
            @if($categories && $categories->count()!=0)
                @foreach($categories as $category)
                    <option value="{{$category->id}}" {{$data && $data->permissions->where('category_id',$category->id)->count()>0 ? 'selected':''}}>{{$category->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>