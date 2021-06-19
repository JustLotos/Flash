import Card from "./Card";

export default class Record {
    private readonly id: number;
    private value: string;
    private readonly side: string;

    constructor({id, value, side} = {}) {
        this.id = id || 0;
        this.value = value || '';
        this.side = side;
    }

    public getId(): number { return this.id }
    public getValue(): string { return this.value }
    public setValue(value: string) { this.value = value }
    public isFront(): boolean { return this.side === 'frontSide' }
    public isBack(): boolean { return this.side === 'backSide'}

    public static parseJSON(data: any): Record {
        let recordString: string = JSON.stringify(data);
        let parsedDeck = JSON.parse(recordString,function (key, value) {
            if(key === 'card') {
                return (new Card(value)).getId();
            }
            return value;
        });

        return new Record(parsedDeck);
    }
}