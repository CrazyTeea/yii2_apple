<?php

namespace app\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string $color
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $fell_at
 * @property string $status
 * @property int $eaten
 */
class Apple extends \yii\db\ActiveRecord
{
    public static string $STATE_ON_TREE = 'on_tree';
    public static string $STATE_FELLED = 'felled';
    public static string $STATE_ROTTEN = 'rotten';
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fell_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'on_tree'],
            [['eaten'], 'default', 'value' => 0],
            [['color'], 'required'],
            [['created_at', 'updated_at', 'fell_at'], 'safe'],
            [['eaten'], 'integer'],
            [['color', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'fell_at' => 'Fell At',
            'status' => 'Status',
            'eaten' => 'Eaten',
        ];
    }


    public function fellToGround(){

    }

    /**
     * @throws Exception
     */
    public function eat(int $percent): static
    {
        if ($this->status == self::$STATE_ON_TREE) {
            throw new Exception('apple on tree', -1);
        }
        if ($percent + $this->eaten > 100) {
            throw new Exception('cant eat apple more then'. (100 - $this->eaten), -2);
        }
        $this->eaten += $percent;

        $this->save();
        return $this;
    }

    public function getSize(): float|int
    {
        return (100 - $this->eaten) / 100;
    }

}
