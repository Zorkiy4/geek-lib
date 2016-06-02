<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class Book
 * @package app\models
 * Represents single book data model.
 */
class Book extends ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'books';
    }

    public static function getBooks()
    {
        return self::find()
            ->indexBy('id')
            ->all();
    }

    public function attributes()
    {
        return [
            'id',
            'title',
            'descr',
            'cover',
            'file',
            'category_id',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'title' => \Yii::t('app', 'Название'),
            'descr' => \Yii::t('app', 'Описание'),
            'cover' => \Yii::t('app', 'Фото обложки'),
            'file' => \Yii::t('app', 'Файл'),
            'category_id' => \Yii::t('app', 'Категория'),
        ];
    }

    public function rules()
    {
        return [
            [['title', 'category_id', 'cover', 'file'], 'required', 'on' => self::SCENARIO_CREATE],
            [['title', 'category_id'], 'required', 'on' => self::SCENARIO_UPDATE],
            ['descr', 'string', 'length' => [0, 255]],
            ['category_id', 'exist', 'targetClass' => '\app\models\Category', 'targetAttribute' => 'id'],
            [['cover'], 'image', 'extensions' => ['jpg', 'png', 'gif']],
            [['file'], 'file', 'extensions' => ['pdf', 'epub']],
        ];
    }

    /**
     * Relation with Category.
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Uploads Cover and Book files into /uploads folder.
     * If no new files was uploaded, saves old file names.
     * @return bool
     */
    public function upload()
    {
        $res = true;
        if ($this->cover instanceof UploadedFile) {
            $this->removeOldUploadedFile($this->getOldAttribute('cover'));
            $res &= $this->saveWithRenaming($this->cover);
        } else {
            $this->cover = $this->getOldAttribute('cover');
        }
        if ($this->file instanceof UploadedFile) {
            $this->removeOldUploadedFile($this->getOldAttribute('file'));
            $res &= $this->saveWithRenaming($this->file);
        } else {
            $this->file = $this->getOldAttribute('file');
        }
        return $res;
    }

    /**
     * Deletes previously uploaded file
     * @param string $filename Name of the file in /uploads folder to be deleted
     */
    protected function removeOldUploadedFile($filename)
    {
        if ($filename != '' && file_exists('uploads/' . $filename)) unlink('uploads/' . $filename);
    }

    /**
     * Saves uploaded file in /uploads folder.
     * Renames it if there is already a file with the same name in the folder.
     * @param \yii\web\UploadedFile $file UploadedFile instance to save.
     * @return bool Successful or not.
     */
    protected function saveWithRenaming(UploadedFile &$file)
    {
        $filename = 'uploads/' . $file->baseName . '.' . $file->extension;
        $new_name = $file->baseName;
        $idx = 1;
        while (file_exists($filename)) {
            $new_name = $file->baseName . $idx;
            $filename = 'uploads/' . $new_name . '.' . $file->extension;
            $idx++;
        }
        $file->name = $new_name . '.' . $file->extension;
        if ($file->saveAs($filename)) {
            return true;
        } else return false;
    }

    public static function searchBooks($str)
    {
        return static::find()
            ->where(['like', 'title', $str])
            ->orderBy('title')
            ->all();
    }
}