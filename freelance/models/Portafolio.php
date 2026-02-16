<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "portafolio".
 *
 * @property int $por_id Código identificador del registro
 * @property int $soc_id Código del socio
 * @property string $por_titulo Título del proyecto
 * @property string $por_descripcion Descripción del proyecto
 * @property string|null $por_imagenes Imágenes del proyecto
 * @property int $por_eliminado Campo para indicar si el registro está eliminado
 * @property string $created_at Fecha de creación
 * @property string $updated_at Fecha de última actualización
 *
 * @property Socio $soc
 */
class Portafolio extends \yii\db\ActiveRecord
{
    public $imageFiles; // For handling file uploads

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portafolio';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['soc_id', 'por_titulo', 'por_descripcion'], 'required'],
            [['soc_id', 'por_eliminado'], 'integer'],
            [['por_descripcion', 'por_imagenes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['por_titulo'], 'string', 'max' => 255],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, webp', 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'por_id' => 'ID',
            'soc_id' => 'Socio',
            'por_titulo' => 'Título del Proyecto',
            'por_descripcion' => 'Descripción del Proyecto',
            'por_imagenes' => 'Imágenes',
            'por_eliminado' => 'Eliminado',
            'created_at' => 'Fecha de Creación',
            'updated_at' => 'Fecha de Actualización',
            'imageFiles' => 'Imágenes del Proyecto',
        ];
    }

    /**
     * Gets query for [[Soc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoc()
    {
        return $this->hasOne(Socio::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Upload and save images
     * @return bool
     */
    public function uploadImages()
    {
        if ($this->imageFiles) {
            $uploadPath = 'uploads/portafolio/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $imagePaths = [];
            foreach ($this->imageFiles as $file) {
                $fileName = uniqid('portfolio_') . '.' . $file->extension;
                $filePath = $uploadPath . $fileName;
                if ($file->saveAs($filePath)) {
                    $imagePaths[] = $filePath;
                }
            }

            if (!empty($imagePaths)) {
                // Merge with existing images if updating
                $existingImages = $this->por_imagenes ? explode(',', $this->por_imagenes) : [];
                $allImages = array_merge($existingImages, $imagePaths);
                $this->por_imagenes = implode(',', $allImages);
                return true;
            }
        }
        return false;
    }

    /**
     * Get array of image paths
     * @return array
     */
    public function getImageArray()
    {
        if (empty($this->por_imagenes)) {
            return [];
        }
        return explode(',', $this->por_imagenes);
    }

    /**
     * Delete a specific image
     * @param string $imagePath
     * @return bool
     */
    public function deleteImage($imagePath)
    {
        $images = $this->getImageArray();
        $key = array_search($imagePath, $images);
        
        if ($key !== false) {
            unset($images[$key]);
            $this->por_imagenes = implode(',', $images);
            
            // Delete physical file
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
            
            return $this->save(false, ['por_imagenes']);
        }
        
        return false;
    }


    /**
     * Scope to get only active (non-deleted) records
     * @param \yii\db\ActiveQuery $query
     * @return \yii\db\ActiveQuery
     */
    public static function active($query)
    {
        return $query->andWhere(['portafolio.por_eliminado' => 0]);
    }
}
