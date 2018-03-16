<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Article;
class AddSlugToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->after('title');
        });
        $articles = Article::all();
        foreach($articles as $article){
           $article->slug = mb_strlen($article->title, mb_detect_encoding($article->title)) == strlen($article->title) ? str_slug($article->title) : urlencode($article->title);
            $article->save();
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {

            $table->dropUnique('articles_slug_unique');
            $table->dropColumn('slug');
        });
    }
}
