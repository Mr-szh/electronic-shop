<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOuts extends Model
{
    protected $fillable = ['id', 'data', 'time_expire'];

    protected $table = 'timeouts';

    public $timestamps = false;

    // 是否过期
    public function getIsExpired()
    {
        return time() > $this->time_expire;
    }

    // 取出数据, 不存在返回 false
    public static function get($id)
    {
        $model = self::query()->find($id);

        if ($model === null || $model->getIsExpired()) {
            return false;
        }

        // return $model->data;
        return true;
    }

    // 删除数据
    public static function del($id)
    {
        $model = self::query()->find($id);
        return $model->delete();
    }

    // 存入限时数据, $extendOnSame 值相当时延时做处理
    public static function put($id, $ttl, $data = '', $extendOnSame = false)
    {
        $model = self::query()->find($id);

        if ($model === null) {
            $model = self::create([
                'id' => $id,
                'time_expire' => time() + $ttl,
                'data' => $data,
            ]);
        } else {
            $now = time();

            if ($extendOnSame && $data === $model->data && $model->time_expire > $now) {
                $model->time_expire += $ttl;
            } else {
                $model->time_expire = $now + $ttl;
            }

            $model->data = $data;
        }
        
        $model->save();
    }
}
