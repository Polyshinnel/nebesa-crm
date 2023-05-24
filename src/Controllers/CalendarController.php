<?php


namespace App\Controllers;


use App\Repository\DealRepository;

class CalendarController
{
    private DealRepository $dealRepository;

    public function __construct(DealRepository $dealRepository)
    {
        $this->dealRepository = $dealRepository;
    }

    public function getCalendar($mouth, $year):array {
        $dayWeekArr = [
            '1' => [
                'day_week' => 'Понедельник',
                'days' => []
            ],
            '2' => [
                'day_week' => 'Вторник',
                'days' => []
            ],
            '3' => [
                'day_week' => 'Среда',
                'days' => []
            ],
            '4' => [
                'day_week' => 'Четверг',
                'days' => []
            ],
            '5' => [
                'day_week' => 'Пятница',
                'days' => []
            ],
            '6' => [
                'day_week' => 'Суббота',
                'days' => []
            ],
            '7' => [
                'day_week' => 'Воскресенье',
                'days' => []
            ]
        ];
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $mouth, $year);
        $endDay = $dayInMonth;
        for($i = 1; $i <= $endDay; $i++) {
            $day = $i;
            if(strlen($day) < 2) {
                $day = '0'.$day;
            }
            $date = "$year-$mouth-$day";
            $time = strtotime($date);
            $dayOfWeek = date('w', $time);
            if($dayOfWeek == 0) {
                $dayOfWeek = 7;
            }
            $events = [];

            $filter = [
                'deals.funnel_id' => 1,
                ['stages.visible','!=','0']
            ];
            $dateStart = $date.' 00:00:00';
            $dateEnd = $date.' 23:59:59';
            $res = $this->dealRepository->getFilteredByDateDeals($dateStart, $dateEnd, $filter);
            if(!empty($res)) {
                $events[] = 'memorial';
            }

            $filter = [
                'deals.funnel_id' => 2,
                ['stages.visible','!=','0']
            ];

            $res = $this->dealRepository->getFilteredByDateDeals($dateStart, $dateEnd, $filter);
            if(!empty($res)) {
                $events[] = 'landscaping';
            }


            $dayWeekArr[$dayOfWeek]['days'][] = [
                'day' => $day,
                'date' => $date,
                'events' => $events
            ];
        }

        $endPosition = '';
        foreach($dayWeekArr as $key=>$value) {
            if($value['days'][0]['day'] == '01') {
                $endPosition = $key;
                break;
            }
        }


        if($endPosition != '1') {
            for($i = 1; $i < $endPosition; $i++) {
                array_unshift($dayWeekArr[$i]['days'],['day' => 'empty']);
            }
        }

        return $dayWeekArr;
    }

    public function getMonthName($month) {
        $mouthArr = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь'
        ];

        return $mouthArr[$month];
    }

    public function getDateLink($mouth, $year) {
        $prevMouth = (int)$mouth - 1;
        $nextMouth = (int)$mouth + 1;

        if(strlen($prevMouth) < 2) {
            $prevMouth = '0'.$prevMouth;
        }

        if(strlen($nextMouth) < 2) {
            $nextMouth = '0'.$nextMouth;
        }

        $prevYear = $year;
        $nextYear = $year;

        if($mouth == '01') {
            $prevMouth = '12';
            $prevYear = $year - 1;
        }

        if($mouth == '12') {
            $nextMouth = '01';
            $nextYear = $year+1;
        }

        return [
            'prevLink' => "/calendar?mouth=$prevMouth&year=$prevYear",
            'nextLink' => "/calendar?mouth=$nextMouth&year=$nextYear"
        ];
    }
}