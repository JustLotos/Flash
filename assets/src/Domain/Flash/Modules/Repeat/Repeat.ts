import Card from "../Card/Card";

export default class Repeat {
    private readonly id: number;
    private readonly createdAt: string;
    private readonly updatedAt: string
    private readonly time: string;
    private readonly ratingScore: number;
    private readonly card: string;

    constructor({id, createdAt, updatedAt, time, ratingScore, card} = {}) {
        this.id = id;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
        this.time = time;
        this.ratingScore = ratingScore;
        this.card = card;
    }

    get getId(): number { return this.id }
    get getCard(): string { return this.card }
    get getCreatedAt() { return Date.parse(this.createdAt); }
    get getUpdatedAt(): string { return this.updatedAt; }
    get getTime(): string { return this.time; }
    get getRatingScore(): number { return this.ratingScore; }

    public static parseJSON(data: any) {
        let repeatString: string = JSON.stringify(data);
        let parsedRepeat = JSON.parse(repeatString,function (key, value) {
            if (key === 'card') {
                return (new Card(value)).getId();
            }

            if(key === 'createdAt' || key === 'updatedAt') {
                let d = new Date(value);
                let ye = new Intl.DateTimeFormat('ru', { year: '2-digit' }).format(d);
                let mo = new Intl.DateTimeFormat('ru', { month: '2-digit' }).format(d);
                let da = new Intl.DateTimeFormat('ru', { day: '2-digit' }).format(d);
                let hr = new Intl.DateTimeFormat('ru', { hour: '2-digit' }).format(d);
                return `${ye} / ${mo} / ${da}:${hr}`
            }

            return value;
        });
        return new Repeat(parsedRepeat);
    }
}