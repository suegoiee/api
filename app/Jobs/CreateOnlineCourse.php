<?php

namespace App\Jobs;

use App\Tag;
use App\OnlineCourse;
use App\Http\Requests\OnlineCourseRequest;

final class CreateOnlineCourse
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $introduction;

    /**
     * @var int
     */
    private $quota;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $interested;

    /**
     * @var string
     */
    private $Suitable;

    /**
     * @var datetime
     */
    private $date;
    
    public function __construct(string $name, string $introduction, int $quota, array $tags = [], string $date/*, int $status, string $image, string $host, int $interested, string $Suitable*/)
    {
        $this->name = $name;
        $this->introduction = $introduction;
        $this->quota = $quota;
        $this->tags = $tags;
        $this->date = $date;
        /*$this->status = $status;
        $this->image = $image;
        $this->host = $host;
        $this->interested = $interested;
        $this->Suitable = $Suitable;*/
    }

    public static function fromRequest(OnlineCourseRequest $request): self
    {
        return new static(
            $request->name(),
            $request->introduction(),
            $request->quota(),
            $request->tags(),
            $request->date()
            /*$request->image(),
            $request->host(),
            $request->interested(),
            $request->Suitable()*/
        );
    }

    public function handle(): OnlineCourse
    {
        $online_course = new OnlineCourse([
            'name' => $this->name,
            'introduction' => $this->introduction,
            'quota' => $this->quota,
            'tags' => $this->tags,
            'date' => $this->date,
            /*'status' => $this->status,
            'image' => $this->image,
            'host' => $this->host,
            'interested' => $this->interested,
            'Suitable' => $this->Suitable,*/
        ]);
        $online_course->save();

        return $online_course;
    }
}
