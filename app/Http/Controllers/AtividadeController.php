<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Objetivo;
use App\Aluno;
use App\Atividade;
use DateTime;

class AtividadeController extends Controller
{
  public static function cadastrar($id_objetivo){
    $statuses = ["Não iniciada", "Em andamento", "Finalizada"];
    $prioridades = ["Alta","Média","Baixa"];
    $objetivo = Objetivo::find($id_objetivo);
    $aluno = $objetivo->aluno;

    return view("atividade.cadastrar", [
      'aluno' => $aluno,
      'objetivo' => $objetivo,
      'statuses' => $statuses,
      'prioridades' => $prioridades
    ]);
  }

  public static function editar($id_atividade){
    $atividade = Atividade::find($id_atividade);
    $objetivo = $atividade->objetivo;
    $aluno = $atividade->objetivo->aluno;

    $statuses = ["Não iniciada", "Em andamento", "Finalizada"];
    $prioridades = ["Alta","Média","Baixa"];

    return view("atividade.editar", [
      'aluno' => $aluno,
      'objetivo' => $objetivo,
      'atividade' => $atividade,
      'statuses' => $statuses,
      'prioridades' => $prioridades
    ]);
  }

  public static function ver($id_atividade){
    $atividade = Atividade::find($id_atividade);
    $objetivo = $atividade->objetivo;
    $aluno = $atividade->objetivo->aluno;

    return view("atividade.ver", [
      'aluno' => $aluno,
      'objetivo' => $objetivo,
      'atividade' => $atividade,
    ]);
  }

  public static function excluir($id_atividade){
    $atividade = Atividade::find($id_atividade);
    $objetivo = $atividade->objetivo;
    $atividade->delete();

    return redirect()->route("objetivo.gerenciar", ["id_objetivo" => $atividade->objetivo->id])->with('success','A atividade '.$atividade->titulo.' foi excluída.');;
  }

  public static function criar(Request $request){
    $validator = Validator::make($request->all(), [
      'titulo' => ['required','min:2','max:100'],
      'descricao' => ['max:500'],
      'prioridade' => ['required'],
      'status' => ['required'],
    ]);

    if($validator->fails()){
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $statuses = ["Não iniciada", "Em andamento", "Finalizada"];

    $atividade = new Atividade();
    $atividade->titulo = $request->titulo;
    $atividade->descricao = $request->descricao;
    $atividade->prioridade = $request->prioridade;
    $atividade->status = $statuses[$request->status];
    $atividade->data = new DateTime();
    $atividade->objetivo_id = $request->id_objetivo;
    $atividade->save();

    return redirect()->route("atividade.ver", ["id_atividade" => $atividade->id])->with('success','Atividade cadastrada.');
  }

  public static function atualizar(Request $request){
    $validator = Validator::make($request->all(), [
      'titulo' => ['required','min:2','max:100'],
      'descricao' => ['max:500'],
      'prioridade' => ['required'],
      'status' => ['required'],
    ]);

    if($validator->fails()){
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $statuses = ["Não iniciada", "Em andamento", "Finalizada"];

    $atividade = Atividade::find($request->id_atividade);
    $atividade->titulo = $request->titulo;
    $atividade->descricao = $request->descricao;
    $atividade->prioridade = $request->prioridade;
    $atividade->status = $statuses[$request->status];
    $atividade->update();

    return redirect()->route("atividade.ver", ["id_atividade" => $atividade->id])->with('success','A atividade '.$atividade->titulo.' foi atualizada.');
  }

  // public static function listar($id_objetivo){
  //
  //   $objetivo = Objetivo::find($id_objetivo);
  //   $aluno = $objetivo->aluno;
  //   $atividades = $objetivo->atividades;
  //
  //   return view("atividade.listar", [
  //     'aluno' => $aluno,
  //     'objetivo' => $objetivo,
  //     'atividades' => $atividades
  //   ]);
  // }

  public static function concluir($id_atividade){

    $atividade = Atividade::find($id_atividade);
    $objetivo = $atividade->objetivo;
    $atividade->concluido = True;
    $atividade->update();

    return redirect()->route("atividade.ver", ["id_atividadeo" => $atividade->id]);
  }

  public static function desconcluir($id_atividade){


    $atividade = Atividade::find($id_atividade);
    $objetivo = $atividade->objetivo;
    $atividade->concluido = False;
    $atividade->update();

    return redirect()->route("atividade.ver", ["id_atividadeo" => $atividade->id]);
  }

  public static function corStatus($status){
    switch ($status) {
      case 'Não iniciada':
        return '#e88d76';
        break;
      case 'Em andamento':
        return '#f2ee74';
        break;
      case 'Finalizada':
        return '#c0e876';
        break;
    }
  }

}