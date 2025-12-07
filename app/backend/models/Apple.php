<?php

namespace backend\models;

use DateInterval;
use DateTime;
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
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            if (isset($changedAttributes['status'])) {
                $this->handleStatusChange($changedAttributes['status'], $this->status);
            }
            $this->handleRottenEvent();

        }



    }

    /**
     * @throws \yii\db\Exception
     */
    protected function handleStatusChange(string $oldStatus, string $newStatus): void
    {
        if ($newStatus == self::$STATE_FELLED) {
            $this->fell_at = date('Y-m-d H:i:s');
            $this->rotten_at = new DateTime()->add(new DateInterval('PT5H'))->format('Y-m-d H:i:s');
            $this->save();
        }
    }

    protected function handleRottenEvent(): void{
        if ($this->rotten_at && $this->status != Apple::$STATE_ROTTEN && (new DateTime() >= new DateTime($this->rotten_at))){
            $this->status = Apple::$STATE_ROTTEN;
            $this->active = false;
            $this->save();
        }
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
            [['created_at', 'updated_at', 'fell_at', 'rotten_at'], 'safe'],
            [['eaten', 'active'], 'integer'],
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

    /**
     * @throws Exception
     */
    public function setColor(?string $color = null, bool $randomize = true): void
    {
        if (!$randomize and is_null($color)) {
            throw new Exception('Color cannot be null or empty. if randomize is false, set this color');
        }
        $colors = ['red', 'green', 'yellow'];
        $this->color = $randomize ? $colors[array_rand($colors)] : $color;
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
