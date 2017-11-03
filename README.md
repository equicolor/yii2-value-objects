"# yii2-value-objects" 

Example usage

```php
<?php
use equicolor\ValueObjectList;
use equicolor\ValueObject;

use yii\db\ActiveRecord; 

use equicolor\valueObjects\ValueObjectsBehavior;

/**
 * @property integer $id
 * @property Offer $offer
 */
class Test extends ActiveRecord
{
    public function behaviors()
    {
        return [
            ValueObjectsBehavior::className(),
        ];
    }
    /**
     * Value objects map
     *
     * @return void
     */
    public static function valueObjects() {
        return [
            'offer' => new Offer,
        ];
    }

    // other methods ...
}

class Offer extends ValueObject {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    public $id;
    public $title;
    public $status;

    public static function valueObjects() {
        return [
            'goals' => ValueObjectList::create(Goal::className()),
            'targeting' => new class extends ValueObject {
                public $country;
                public $age;
            }
        ];
    }
}

class Goal extends ValueObject {
    public $id;
    public $event;
    public $stake;
    public $title;

    public function getReward(float $reward) {
        return sprintf('%.2f', ($reward / 100) * $this->stake);
    }
}
```