<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CoinRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetExchangeRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     protected $tokenName;

    protected $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $tokenName, $date = null)
    {
        $this->tokenName = $tokenName;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $apiKey = config('coinlayer.api_key');
        $date = $this->date;
        $tokenName = $this->tokenName;
        $baseUrl = $date ? 'http://api.coinlayer.com/api/' . $date . '?access_key=' . $apiKey
            : 'http://api.coinlayer.com/api/live?access_key=' . $apiKey;
        $link = $baseUrl . '&symbols=' . $tokenName;

        $response =  Http::get($link);

        if ($response->successful()) {
            $info = $response->json()['rates'];
            $model = new CoinRate;

            $model->token_name = key($info);
            $model->token_rate = $info[key($info)];

            if (!$date) {
                $model->historical_date = now()->format('Y-m-d');
            } else {
                $model->historical_date = $date;
            }

            $model->save();
        }
    }
}
