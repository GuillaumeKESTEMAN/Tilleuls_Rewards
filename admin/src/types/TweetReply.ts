export class TweetReply {
    constructor(
        _id?: string,
        public id?: string,
        public name?: string,
        public message?: string,
    ) {
        this['@id'] = _id;
    }

    public '@id'?: string;
}
