export class TwitterAccountToFollow {
    constructor(
        _id?: string,
        public id?: string,
        public twitterAccountName?: string,
        public twitterAccountId?: string,
        public active?: boolean,
    ) {
        this['@id'] = _id;
    }

    public '@id'?: string;
}
