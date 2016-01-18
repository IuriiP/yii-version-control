<?php namespace iuriip\yii2;

use \yii\db\ActiveQuery;

trait VersionControl {

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
        // Provided marker for using in `where`. 
        // VC not using the "with providing" capability if empty
        'provide' => false,
    ];

    /**
     * Get last version.
     * 
     * @param mixed $forEdit condition if last unprovided record must be visible.
     * I.e. author or moderator need access it
     */
    public function version($forEdit = false) {
        if (!empty($this->versionControl['branch'])) {
            /* @var $class string */
            $class = $this->modelClass;
            /* @var $vcVersion string */
            $vcVersion = empty($this->versionControl['version']) ?
                    // PK is unique
                    $class::primaryKey()
                    // Need unique index
                    : "CONCAT({$this->versionControl['branch']},{$this->versionControl['version']})";
            /* @var $subQuery \yii\db\ActiveQuery */
            $subQuery = (new ActiveQuery($class))
                    ->select(["MAX({$vcVersion})"])
                    ->groupBy($this->versionControl['branch']);
            if (!empty($this->versionControl['provide'])) {
                if ($forEdit) {
                    $subQuery = $subQuery
                            ->orWhere($forEdit);
                }
                $subQuery = $subQuery
                            ->orWhere($this->versionControl['provide']);
            }
            return $this->andWhere(['in', $vcVersion, $subQuery]);
        }
        return $this;
    }

}
