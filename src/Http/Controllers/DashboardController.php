<?php

namespace Canvas\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\View;
use Canvas\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use DateInterval;
use DatePeriod;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    protected int $period;
    protected array $lookup;
    protected array $lookback;

    public function __construct()
    {
        $this->getRangeLookups();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $postIds = Post::query()
            ->published()
            ->when(request()->query('scope', 'user') === 'all', function (Builder $query) {
                return $query;
            }, function (Builder $query) {
                return $query->where('user_id', request()->user('canvas')->id);
            })
            ->pluck('id')
            ->toArray();

        $lookupViews = View::query()
                ->select('id')
                ->whereBetween('created_at', [
                    $this->lookup['start'],
                    $this->lookup['end'],
                ])
                ->whereIn('post_id', $postIds)
                ->count();

        $lookbackViews = View::query()
                ->select('id')
                ->whereBetween('created_at', [
                    $this->lookback['start'],
                    $this->lookback['end'],
                ])
                ->whereIn('post_id', $postIds)
                ->count();

        $lookupVisits = Visit::query()
                        ->select('id')
                        ->whereBetween('created_at', [
                            $this->lookup['start'],
                            $this->lookup['end'],
                        ])
                        ->whereIn('post_id', $postIds)
                        ->count();

        $lookbackVisits = Visit::query()
                        ->select('id')
                        ->whereBetween('created_at', [
                            $this->lookback['start'],
                            $this->lookback['end'],
                        ])
                        ->whereIn('post_id', $postIds)
                        ->count();

        return response()->json([
            [
                'name' => 'Total pageviews',
                'count' => $lookupViews,
                'change' => $this->percentOfChange($lookupViews, $lookbackViews),
            ],
            [
                'name' => 'Unique Visitors',
                'count' => $lookupVisits,
                'change' => $this->percentOfChange($lookupVisits, $lookbackVisits),
            ],
        ]);
    }

    public function chart(): JsonResponse
    {
        $postIds = Post::query()
            ->published()
            ->when(request()->query('scope', 'user') === 'all', function (Builder $query) {
                return $query;
            }, function (Builder $query) {
                return $query->where('user_id', request()->user('canvas')->id);
            })
            ->pluck('id')
            ->toArray();


        $views = View::query()
                ->select('created_at')
                ->whereBetween('created_at', [
                    $this->lookup['start'],
                    $this->lookup['end'],
                ])
                ->whereIn('post_id', $postIds)
                ->get();

        $visits = Visit::query()
            ->select('created_at')
            ->whereBetween('created_at', [
                $this->lookup['start'],
                $this->lookup['end'],
            ])
            ->whereIn('post_id', $postIds)
            ->get();

        return response()->json([
            'views' => $this->datePlots($views)->toJson(),
            'visits' => $this->datePlots($visits)->toJson(),
        ]);
    }

    // public function sources(): JsonResponse
    // {
    //     # code...
    // }

    // public function pages(): JsonResponse
    // {
    //     # code...
    // }

    // public function countries(): JsonResponse
    // {
    //     # code...
    // }

    // public function devices(): JsonResponse
    // {
    //     # code...
    // }

    protected function getRangeLookups(): void
    {
        $from = request('date', now());
        $period = request('period', '30d');

        switch ($period) {
            case 'day':
                $primaryEnd = Carbon::parse($from)->endOfDay()->toDateTimeString();
                $primaryStart = Carbon::parse($primaryEnd)->startOfDay()->toDateTimeString();
                break;

            case '7d':
                $primaryStart = $primaryEnd->subDays(7)->startOfDay();
                break;

            case '30d':
                $primaryStart = $primaryEnd->subDays(30)->startOfDay();
                break;

            case 'month':
                $primaryStart = $primaryEnd->startOfDay();
                break;

            case '6mo':
                $primaryStart = $primaryEnd->startOfDay();
                break;

            case '12mo':
                $primaryStart = $primaryEnd->startOfDay();
                break;

            default:
                # code...
                break;
        }




        $days = $primaryStart->diffInDays($primaryEnd);

        $secondaryStart = $primaryStart->copy()->subDays($days)->startOfDay();
        $secondaryEnd = $primaryStart->copy()->startOfDay();

        // return [
        //     'period' => $days,
        //     'lookup' => [
        //         'start' => $primaryStart->toDateTimeString(),
        //         'end' => $primaryEnd->toDateTimeString(),
        //     ],
        //     'lookback' => [
        //         'start' => $secondaryStart->toDateTimeString(),
        //         'end' => $secondaryEnd->toDateTimeString(),
        //     ],
        // ];
    }

    protected function datePlots(Collection $data): Collection
    {
        // Filter the data to only include created_at date strings
        $filtered = new Collection();

        $data->sortBy('created_at')->each(function ($item) use ($filtered) {
            $filtered->push($item->created_at->toDateString());
        });

        // Count the unique values and assign to their respective keys
        $unique = array_count_values($filtered->toArray());

        // Create a day range to hold the default date values

        // this works for day breakdowns
        $period = $this->generateDateRange(
            Carbon::create($this->lookback['start']),
            CarbonInterval::days(),
            $this->period,
            DatePeriod::EXCLUDE_START_DATE,
            'Y-m-d'
        );

        // this works for hourly breakdowns
        // $period = $this->generateDateRange(
        //     Carbon::create($this->lookup['start']),
        //     CarbonInterval::hours(),
        //     24,
        //     DatePeriod::EXCLUDE_START_DATE,
        //     'g:i A'
        // );


        dd($period);

        // Compare the data and date range arrays, assigning counts where applicable
        $results = new Collection();

        foreach ($period as $date) {
            if (array_key_exists($date, $unique)) {
                $results->put($date, $unique[$date]);
            } else {
                $results->put($date, 0);
            }
        }

        return $results;
    }

    /**
     * Return the percentage of change between two given numbers.
     *
     * @param string|int $numberOne
     * @param string|int $numberTwo
     * @return float|int
     */
    protected function percentOfChange(string|int $numberOne, string|int $numberTwo): float|int
    {
        $difference = (int) $numberOne - (int) $numberTwo;

        $change = $numberOne != 0 ? ($difference / $numberTwo) * 100 : $numberOne * 100;

        return round($change, 1);
    }

    /**
     * Return an array of formatted date/time strings.
     *
     * @param DateTimeInterface $start_date
     * @param DateInterval $interval
     * @param int $recurrences
     * @param int $exclusive
     * @param string $format
     * @return array
     */
    protected function generateDateRange(
        DateTimeInterface $start_date,
        DateInterval $interval,
        int $recurrences,
        int $exclusive = 1,
        string $format = 'Y-m-d'
    ): array {
        $period = new DatePeriod($start_date, $interval, $recurrences, $exclusive);
        $dates = new Collection();

        foreach ($period as $date) {
            $dates->push($date->format($format));
        }

        return $dates->toArray();
    }
}
