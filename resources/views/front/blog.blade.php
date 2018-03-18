<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="no-js ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>

   <!--- Basic Page Needs
   ================================================== -->
   <meta charset="utf-8">
	<title>優分析 Uanalyze - UA專欄</title>
	<meta name="description" content="">  
	<meta name="author" content="">

	<!-- mobile specific metas
   ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <!-- CSS
    ================================================== -->
   <link rel="stylesheet" href="{{asset('css/front/default.css')}}">
   <link rel="stylesheet" href="{{asset('css/front/layout.css')}}">  
   <link rel="stylesheet" href="{{asset('css/front/media-queries.css')}}"> 

   <!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="{{url('favicon.png')}}" >

</head>

<body>

   <!-- Header
   ================================================== -->
   <header id="top">


	   <nav id="nav-wrap"> 
   		<div class="logo"></div>
	   	<a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show Menu</a>
		   <a class="mobile-btn" href="#" title="Hide navigation">Hide Menu</a>

	   	<div style="display:inline">  
			   	<ul id="nav" class="nav">
	            	<li><a  class="btn-orange" href="https://pro.uanalyze.com.tw">UA Pro</a></li>		               
			   	</ul> <!-- end #nav -->		
	   	</div> 

	   </nav> <!-- end #nav-wrap --> 	     

   </header> <!-- Header End -->

   <!-- Content
   ================================================== -->
   <div id="content-wrap">

   	<div class="row">

   		<div id="main" class="eight columns">
   			@foreach($articles as $article)
	   		<article class="entry">

					<header class="entry-header">

						<h2 class="entry-title">
							<a href="{{url('archives/'.$article->slug)}}" title="{{$article->title}}">{{str_limit($article->title,60)}}</a>
						</h2> 				 
					
						<div class="entry-meta">
							<ul>
								<li>{{str_limit($article->posted_at)}}</li>
								@foreach($article->tags as $tag)
									<span class="meta-sep">&bull;</span>
									<li>{{$tag->name}}</li>
								@endforeach
							</ul>
						</div> 
					 
					</header> 
					
					<div class="entry-content">
						{!!str_limit(strip_tags(preg_replace("/<img[^>]+\>/i", "", $article->content)),500)!!}
					</div> 

				</article> <!-- end entry -->
				<hr/>
				@endforeach
				<div class="pagenav">
		            {!! $articles->links() !!}
            	</div> 	


   		</div> <!-- end main -->

   		<div id="sidebar" class="four columns">


   			<div class="widget widget_categories group">
   				<ul>
						<li><a href="{{url('blogs')}}" title="">UA專欄</a> ({!! $articles->count() !!})</li>
	
					</ul>
				</div>

   			
   		</div> <!-- end sidebar -->

   	</div> <!-- end row -->

   </div> <!-- end content-wrap -->
   

</body>

</html>