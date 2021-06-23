
const MINUTE = 60;
const HOUR = 3600;
const DAY = 86400;

export class DateHelper {
    private readonly date: Date;

    constructor(date: Date) {
        this.date = date;
    }

    public static formatInterval(timeStamp): string {
        let result: string = '';

        if(timeStamp >= DAY) {
            return (Math.ceil((timeStamp / DAY))).toString() + ' д';
        }

        if(timeStamp >= HOUR) {
            return ((timeStamp / HOUR).toFixed(2)).toString() + ' ч';
        }

        if(timeStamp >= MINUTE) {
            return ((timeStamp / MINUTE).toFixed(2)).toString() + ' мин';
        }

        return  result;
    }
}