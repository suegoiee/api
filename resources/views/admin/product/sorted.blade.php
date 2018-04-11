<li class="list-group-item list-group-header">
    <div class="cursor"></div>
    <div class="pic">{{trans($module_name.'.admin.avatar_small')}}</div>
    <div class="name">{{trans($module_name.'.admin.name')}}</div>
    <div class="type">{{trans($module_name.'.admin.type')}}</div>
    <div class="category">{{trans($module_name.'.admin.tags')}}</div>
</li>
<ul id="sortable" class="list-group mb-5">
    @foreach($products as $key=>$product)
        <li class="ui-state-default list-group-item" data-toggle="tooltip" data-placement="bottom" title="{{$product->name}}">
             <div class="cursor"><span class="oi oi-menu"></span><input type="hidden" name="products[]" value="{{$product->id}}"></div>
            <div class="pic">
                @if($product->avatar_small)
                    <img src="{{$product->avatar_small->url}}" alt='pic'>
                @endif
            </div>
            <div class="name">{{$product->name}}</div>
            <div class="type">{{$product->type}}</div>
            <div class="category">
                @foreach($product->tags as $tag)
                    {{$tag->name}}
                @endforeach
            </div>
        </li>
    @endforeach
</ul>
