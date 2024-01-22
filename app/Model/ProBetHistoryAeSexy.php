<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProBetHistoryAeSexy extends Model
{
  protected $table = 'pro_bet_history_ae_sexy';
  protected $fillable = [
    'gameType' ,
    'winAmount' ,
    'settleStatus',
    'realBetAmount',
    'realWinAmount',
    'txTime',
    'updateTime',
    'userId',
    'betType',
    'platform',
    'txStatus',
    'betAmount',
    'gameName',
    'platformTxId',
    'betTime',
    'gameCode',
    'currency',
    'jackpotBetAmount',
    'jackpotWinAmount',
    'turnover',
    'roundId',
    'gameInfo',
    'time123bet',
    'user_parent',
  ];
    public $timestamps = false;
}
