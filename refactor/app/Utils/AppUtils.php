<?php

namespace DTApi\Utils;

class AppUtils {

    public function ignoreExpiring($id) {
        $job = Job::find($id);
        $job->ignore = 1;
        $job->save();
        return ['success', 'Changes saved'];
    }

    public function ignoreExpired($id) {
        $job = Job::find($id);
        $job->ignore_expired = 1;
        $job->save();
        return ['success', 'Changes saved'];
    }

    public function ignoreThrottle($id)
    {
        $throttle = Throttles::find($id);
        $throttle->ignore = 1;
        $throttle->save();
        return ['success', 'Changes saved'];
    }

    /**
     * Convert number of minutes to hour and minute variant
     * @param  int $time
     * @param  string $format
     * @return string
     */
    public static function convertToHoursMins($time, $format = '%02dh %02dmin') {
        if ($time < 60) {
            return $time . 'min';
        } else if ($time == 60) {
            return '1h';
        }

        $hours = floor($time / 60);
        $minutes = ($time % 60);

        return sprintf($format, $hours, $minutes);
    }

    /**
     * Other functions from BookingRepository that is not directly relevant to Core Business Logic can also relocate here
     * In that way, other resources that needs the same logic can just only access this AppUtils without redoing same code implementation
     */

}