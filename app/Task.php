<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Task extends Model
{
    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Indicates if the IDs are auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['date_created'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['type', 'content', 'done'];

    /**
     * The model's attributes.
     * @var array
     */
    protected $attributes = ['done' => false];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
            'type' => 'required|in:work,shopping'
        ];
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->uuid) {
            $this->uuid = Uuid::uuid4();
            $this->date_created = Carbon::now();
            $this->sort_order = self::max('sort_order') + 1;
        }
        return parent::save($options);
    }
}
