<?php namespace iuriip\yii2;

use \yii\db\ActiveQuery;

trait VersionControl {
    /**
     * @var string $vcBranch grouping field name. 
     * VC is disabled if empty
     */
//    public $vcBranch = false;
    /**
     * @var string $vcVersion versions ID field name. 
     * PK used if empty
     */
//    public $vcVersion = false;
    /**
     * @var string $vcProvided provided marker field name. 
     * VC not using the "with providing" capability if empty
     */
//    public $vcProviding = false;

    /**
     * Get last version.
     * 
     * @param boolean $forEdit true if last unprovided record must be visible
     * i.e. author or moderator need access it
     */
    public function version($forEdit = false) {
        if (!empty($this->vcBranch)) {
            /* @var $class string */
            $class = $this->modelClass;
            /* @var $vcVersion string */
            $vcVersion = empty($this->vcVersion) ?
                    $class::primaryKey() : "CONCAT({$this->vcBranch},{$this->vcVersion})";
            /* @var $subQuery \yii\db\ActiveQuery */
            $subQuery = (new ActiveQuery($class))->select(["MAX({$vcVersion})"])->groupBy($this->vcBranch);
            if (!empty($this->vcProviding) && !$forEdit) {
                $subQuery = $subQuery->where($this->vcProviding);
            }
            return $this->andWhere(['in', $vcVersion, $subQuery]);
        }
        return $this;
    }

}
