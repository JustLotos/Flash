import Event from "./Event";

export default class EventEmitter {
    private static instance: EventEmitter;
    public events: Map<string, Array<Function>>;

    private constructor() {
        this.events = new Map<string, Array<Function>>();
    }

    public static i(): EventEmitter {
        if (!EventEmitter.instance) {
            EventEmitter.instance = new EventEmitter();
        }

        return EventEmitter.instance;
    }

    public addEventListener(event: Event, callback: Function) {
        let callbacks = this.events.get(event.name) as Array<Function>;
        if(!callbacks?.length) {
            this.events.set(event.name, [callback]);
            return;
        }

        if(!callbacks.some(innerC => callback === innerC))  {
            callbacks.push(callback);
            this.events.set(event.name, callbacks)
        }
    }

    public emit(event: Event, data: any) {
        let callBacks = this.events.get(event.name) as Array<Function>;
        callBacks.map((callback: Function) => {
            callback(data);
        })
    }

    public clear(event: Event) {
        this.events.delete(event.name);
    }
}