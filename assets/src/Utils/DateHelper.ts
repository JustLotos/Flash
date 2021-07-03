
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

    public static  dateFormat(value) {
        if(!value) return '';
        let d = new Date(value);
        let ye = new Intl.DateTimeFormat('ru', { year: '2-digit' }).format(d);
        let mo = new Intl.DateTimeFormat('ru', { month: '2-digit' }).format(d);
        let da = new Intl.DateTimeFormat('ru', { day: '2-digit' }).format(d);
        let hr = new Intl.DateTimeFormat('ru', { hour: '2-digit' }).format(d);
        let m = new Intl.DateTimeFormat('ru', { minute: '2-digit' }).format(d);
        return `${ye} | ${mo} | ${da} | ${hr}:${m}`
    }

    public static getDiff(value: string, firstDate: Date = new Date()) {
        let date = new Date(value);
        let diff = date.getTime() - firstDate.getTime();
        return {
            dateFormatted: DateHelper.formatInterval(Math.abs(diff / 1000)),
            expired: diff < 0
        };
    }
}