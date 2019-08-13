<?php

namespace App\Http\Requests;

class OnlineCourseRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'string'|'required',
            'date',
            'quota' => 'int',
            'introduction' => 'string',
            /*'image' => 'string',
            'host' => 'string',
            'interested' => 'int',
            'Suitable' => 'string',*/
            'tags' => 'array',
        ];
    }

    public function name(): string
    {
        return $this->get('name');
    }

    public function Suitable(): string
    {
        return $this->get('Suitable');
    }

    public function date()
    {
        return $this->get('date');
    }

    public function introduction(): string
    {
        return $this->get('introduction');
    }

    public function image(): string
    {
        return $this->get('image');
    }

    public function host(): string
    {
        return $this->get('host');
    }

    public function interested(): int
    {
        return $this->get('interested');
    }

    public function quota(): int
    {
        return $this->get('quota');
    }

    public function status(): int
    {
        return $this->get('status');
    }

    public function tags(): array
    {
        return $this->get('tags', []);
    }
}
