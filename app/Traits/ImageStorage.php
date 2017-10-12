<?php 
namespace App\Traits;
use Storage;
use Illuminate\Http\Request;
trait ImageStorage
{
	protected function storeAvatar($file, $module_id, $module='other')
	{
		$directory = 'avatar/'.$module.'/'.$module_id;
	    Storage::disk('public')->makeDirectory($directory);
		return $file->store($directory, 'public');
	}
	protected function createAvatar($file, $module_id, $module='other')
	{
		$directory = 'avatar/'.$module.'/'.$module_id;
	    Storage::disk('public')->makeDirectory($directory);
		$path = Storage::disk('public')->putFile($directory, $file);
		return $path;
	}
	protected function storeImage($file, $dir='other')
	{
		$directory = 'ckeditor/'.$dir;
	    Storage::disk('public')->makeDirectory($directory);
		return $file->store($directory, 'public');
	}
	protected function destroyAvatar($path)
	{
		Storage::disk('public')->delete($path);
	}
}
