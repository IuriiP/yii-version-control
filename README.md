# Version Control extension

Sometimes we all need to have a some VC capability for the records in DB. 
For example - a history of the profile changes or the article editing...
Sometimes we need to have the unprovided changes (visible to the author only) etc.

Here is my simple solution for using with any existing model.
 
All you need is add the `trait` to ActiveQuery class for your model
and define VC settings in `$versionControl`.

~~~php
use \iuriip\yii2-versions\VersionControl;

    /**
     * @var array $versionControl
     */
    public $versionControl = [
        // Grouping field name.
        // VC is disabled if empty
        'branch' => false,
        // Version ID field name. 
        // PK used if empty
        'version' => false,
        // Provided marker field name. 
        // VC not using the "with providing" capability if empty
        'provide' => false,
        ];
~~~

### Meanings

* `branch` is a field name for identify the object with versioning. 
I.e. `user_id` for the user's profiles history, 
`acticle_key` for the article's edit history etc.

* `version` is a field name for identify the versions.
You may omit it and use the more fast versioning over PK (it is Unique and Ascending).
But sometime you need mark the versions by a timestamp or any other ascending value.
You can define own method for filling this field.

* `provide` is a field name or any possible parameter (acceptable for `where`)
for marking the version as **provided**. 
Just define it if you need use this capability.
Once defined a parameter will used for excluding an **unprovided** last record. 
But an unprovided record will be accessed if the `$forEdit` parameter of 
the `version()` method will be `true`.

### Using

You can use the 'version()' method for selecting the last versions 
anywhere you need.

~~~php
    public function search($params)
    {
        $query = Versioned::find()->version(true);
~~~