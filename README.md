
# Yii2 Value Objects behavior

Extend your ActiveRecord by nested objects and typed collections, serialized and stored in table field

Example

```php
class User extends ActiveRecord
{
    // using value objects require attached behavior
    public function behaviors()
    {
        return [
            ValueObjectsBehavior::className(),
        ];
    }

    /**
     * Value objects map
     *
     * @return array
     */
    public static function valueObjects() {
        // define value objects on model attributes
        return [
            // $this->profile attribute will be an instance of defined anonymous class
            'profile' => new class extends ValueObject {
                public $github;
                public $phones = [];
            },
        ];
    }
}

$user = new User();
$user->profile->github = 'https://github.com/equicolor/';
$user->profile->phones[] = '555-55-555';
$user->save();

```
Now ```profile``` field of ```user``` table contains json:

```json
{"github":"https://github.com/equicolor/","phones":["555-55-555"]}
```
It will be converted to object on afterFind event.

A more complex example with collections

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
class Campaign extends ActiveRecord
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
            // you can define value object as simple class
            'offer' => new Offer,
        ];
    }

    // other methods ...
}

// and feel free to use any possibilites of objects and classes
class Offer extends ValueObject {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    public $id;
    public $title;
    public $status;

    public static function valueObjects($model) {
        return [
            // $campaign->offer->goals is array of Goal objects
            'goals' => ValueObjectList::create(Goal::className()),
            'targeting' => new class extends ValueObject {
                public $country;
                public $age;
            }
        ];
    }
}

class Goal extends ValueObject {
    public $stake;
    public $title;

    // you can define your own methods
    public function getReward(float $reward) {
        return sprintf('%.2f', ($reward / 100) * $this->stake);
    }
}

$campaign = Campaign::find()->one();
// $campaign->offer was converted to object on afterFind event

$campaign->offer->status = Offer::STATUS_ACTIVE;
$goal = $campaign->offer->goals[] = new Goal([
    'title' => 'win',
    'stake' => 50
]);
echo $goal->getReward($offer->reward);

// and store changes
$campaign->save();

```

# Roadmap
* Tests
* Validation
* Separate serealization engine

You are wellcome to create issues =)