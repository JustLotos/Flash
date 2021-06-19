export class Deck {
    private readonly id: Number;
    private readonly name: String;
    private readonly description: String;
    private readonly updatedAt: string;
    private cards: Array<Number>;

    // @ts-ignore
    constructor({id, name, updatedAt, description, cards} = {}) {
        this.id = id || 0;
        this.name = name || '';
        this.description = description || '';
        this.updatedAt = updatedAt || '';
        this.cards = cards || [];
    }

    public getId(): Number|String { return this.id }
    public getName(): String { return this.name }
    public getUpdatedAt(): String { return this.updatedAt }
    public getDescription(): String { return this.description }
    public getCards(): Array<Number> { return this.cards }
    public setCards(cards: Array<Number>) { this.cards = cards }

    public getFormattedDate() {
        return new Date(this.updatedAt).toLocaleDateString();
    }

    public static parseJSON(data: any): Deck {
        let deckString: string = JSON.stringify(data);
        let parsedDeck = JSON.parse(deckString,  function (key, value) {
            if(key === 'cards') {
                // @ts-ignore
                return value.map(card => card.id);
            }

            return value;
        });
        return new Deck(parsedDeck);
    }
}