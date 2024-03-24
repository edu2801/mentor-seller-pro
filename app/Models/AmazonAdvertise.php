<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonAdvertise extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'external_sku',
        'title',
        'description',
        'bullet_points',
        'keywords',
        'status',
        'thumbnail',
        'variation',
        'parent_sku',
        'permalink',
        'price',
        'sold_quantity',
        'visits',
        'account_id',
        'geral_grade',
    ];

    public function images()
    {
        return $this->hasMany(AmazonAdvertiseImage::class);
    }

    public function account()
    {
        return $this->belongsTo(UserMarketplaceAccount::class, 'account_id');
    }

    public function aPlusContent()
    {
        return $this->hasMany(AmazonAdvertiseAPlus::class, 'amazon_advertises_item_id', 'item_id');
    }

    public function grades()
    {
        return $this->hasMany(AmazonAdvertiseGrade::class, 'amazon_advertises_id', 'id');
    }

    public static function calcGrade($amazonAdvertise)
    {
        $titleGrade = 0;
        $titleGradeMessage = [];
        $descriptionGrade = 0;
        $descriptionGradeMessage = [];
        $bulletPointsGrade = 0;
        $bulletPointsGradeMessage = [];
        $keyworkdsGrade = 0;
        $keyworkdsGradeMessage = [];
        $imagesGrade = 0;
        $imagesGradeMessage = [];
        $aPlusContentGrade = 0;
        $aPlusContentGradeMessage = [];
        $geralGrade = 0;

        $title = $amazonAdvertise->title;
        if (!empty($title)) {
            if (strlen($title) >= 25) {
                $titleGrade += 0.25;
            } else {
                $titleGradeMessage[] = 'Título deve ter no mínimo 25 caracteres';
            }

            if (strlen($title) >= 50) {
                $titleGrade += 0.25;
            } else {
                $titleGradeMessage[] = 'Para ficar melhor, o título deve ter no mínimo 50 caracteres';
            }

            if (strlen($title) <= 70) {
                $titleGrade += 0.25;
            } else {
                $titleGradeMessage[] = 'Para ficar melhor, o título deve ter no máximo 70 caracteres';
            }

            if (strlen($title) <= 200) {
                $titleGrade += 0.25;
            } else {
                $titleGradeMessage[] = 'Título deve ter no máximo 200 caracteres';
            }
        } else {
            $titleGradeMessage[] = 'Adicione um título para melhorar o seu anúncio.';
        }


        $description = $amazonAdvertise->description;
        if (!empty($description)) {
            if (strlen($description) >= 200) {
                $descriptionGrade += 0.5;
            } else {
                $descriptionGradeMessage[] = 'Descrição deve ter no mínimo 200 caracteres';
            }

            if (str_contains($description, '<p>') || str_contains($description, '<div>')) {
                $descriptionGrade += 0.5;
            } else {
                $descriptionGradeMessage[] = 'Descrição deve conter tags HTML';
            }
        } else {
            $descriptionGradeMessage[] = 'Adicione uma descrição para melhorar o seu anúncio.';
        }

        $bulletPoints = $amazonAdvertise->bullet_points;
        if (!empty($bulletPoints)) {
            $bulletPoints = json_decode($bulletPoints);
            if (count($bulletPoints) >= 3) {
                $bulletPointsGrade += 0.5;
            } else {
                $bulletPointsGradeMessage[] = 'Deve conter no mínimo 3 bullet points';
            }

            if (count($bulletPoints) >= 5) {
                $bulletPointsGrade += 0.5;
            } else {
                $bulletPointsGradeMessage[] = "Você pode adicionar mais bullet points para melhorar a descrição do seu anúncio.";
            }
        } else {
            $bulletPointsGradeMessage[] = 'Adicione bullet points para melhorar o descrição do seu anúncio.';
        }

        $keywords = $amazonAdvertise->keywords;
        if (!empty($keywords)) {
            $keywords = explode(',', $keywords);
            if (count($keywords) >= 5) {
                $keyworkdsGrade += 0.5;
            } else {
                $keyworkdsGradeMessage[] = 'Deve conter no mínimo 5 palavras-chave';
            }

            if (count($keywords) >= 10) {
                $keyworkdsGrade += 0.5;
            } else {
                $keyworkdsGradeMessage[] = "Você pode adicionar mais palavras-chave para melhorar o posicionamento do seu anúncio.";
            }
        } else {
            $keyworkdsGradeMessage[] = 'Adicione palavras-chave para melhorar o posicionamento do seu anúncio.';
        }

        $images = $amazonAdvertise->images;
        if (!empty($images)) {
            if (count($images) >= 3) {
                $imagesGrade += 0.5;
            } else {
                $imagesGradeMessage[] = 'Deve conter no mínimo 4 imagens';
            }

            foreach ($images as $image) {
                if ($image->height >= 1000 && $image->width >= 1000) {
                    $imagesGrade += 0.5 / count($images);
                } else {
                    $imagesGradeMessage[] = 'Imagem devem ter no mínimo 1000px de altura e largura: ' . $image->url;
                }
            }
        } else {
            $imagesGradeMessage[] = 'Adicione imagens para melhorar o posicionamento do seu anúncio.';
        }

        $aPlusContent = $amazonAdvertise->aPlusContent->count();
        if (!empty($aPlusContent)) {
            $aPlusContentGrade += 1;
        } else {
            $aPlusContentGradeMessage[] = 'Adicione Conteúdo A+ para melhorar a descrição do seu produto.';
        }



        $geralGrade = ($titleGrade + $descriptionGrade + $bulletPointsGrade + $keyworkdsGrade + $imagesGrade + $aPlusContentGrade) / 6;
        $amazonAdvertise->geral_grade = $geralGrade;
        $amazonAdvertise->update();

        $amazonAdvertise->grades()->delete();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $titleGrade;
        $grade->type = 'title';
        $grade->message = implode(';', $titleGradeMessage);
        $grade->save();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $descriptionGrade;
        $grade->type = 'description';
        $grade->message = implode(';', $descriptionGradeMessage);
        $grade->save();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $bulletPointsGrade;
        $grade->type = 'bullet_points';
        $grade->message = implode(';', $bulletPointsGradeMessage);
        $grade->save();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $keyworkdsGrade;
        $grade->type = 'keywords';
        $grade->message = implode(';', $keyworkdsGradeMessage);
        $grade->save();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $imagesGrade;
        $grade->type = 'images';
        $grade->message = implode(';', $imagesGradeMessage);
        $grade->save();

        $grade = new AmazonAdvertiseGrade();
        $grade->amazon_advertises_id = $amazonAdvertise->id;
        $grade->grade = $aPlusContentGrade;
        $grade->type = 'a_plus_content';
        $grade->message = implode(';', $aPlusContentGradeMessage);
        $grade->save();
    }
}
