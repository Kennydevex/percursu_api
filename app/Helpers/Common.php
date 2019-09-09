<?php

namespace App\Helpers;

use Category;
use Entity;
use Image;
use Folk;

class Common
{

  //Categorias
  public static function verifyCategory($category_name, $entity_name)
  {
    $category = Category::whereName($category_name)->first();
    if (!$category) {
      $entity = self::findOrCreateEntity($entity_name);
      $category = Category::create([
        'name' => $category_name,
        'description' => "Categoria para a tabela " . $entity_name,
        'entity_id' => $entity->id
      ]);
    }
    return $category;
  }


  //Entidades
  public static function findOrCreateEntity($entity_name)
  {
    $entity = Entity::whereName($entity_name)->first();
    if (!$entity) {
      $entity = Entity::create(['name' => $entity_name]);
    }

    $entity->update([
      'status' => true
    ]);

    return $entity;
  }

  public static function storeLocalFile($requestImage, $filePath)
  {
    if ($requestImage) {
      $image = $requestImage;
      $imageName = time(). '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
      Image::make($requestImage)->save(public_path($filePath) . $imageName);
      return $imageName;
    }
  }


  //Pessoais

  public static function createFolk($request)
  {

    // $folk = Folk::findOrfail($id);
    // if ($folk) {
    //   $folk = Folk::update([
    //     'name'=>$request->name, 
    //     'lastname'=>$request->lastname,
    //     'gender'=>$request->gender, 
    //     'avatar'=>$request->avatar, 
    //     'ic'=>$request->ic, 
    //     'nif'=>$request->nif, 
    //     'birthdate'=>$request->birthdate, 
    //   ]);
    //   return $folk;
    // }
    $folk = Folk::create([
      'name' => $request->name,
      'lastname' => $request->lastname,
      'gender' => $request->gender,
      'avatar' => $request->avatar,
      'ic' => $request->ic,
      'nif' => $request->nif,
      'birthdate' => $request->birthdate,
    ]);
    return $folk;
  }
}
