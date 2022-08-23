import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {NAME_CHOICES} from "../../config/components/tweetReply.ts";

interface Props {
    tweetReply: TweetReply;
}

export const Show: FunctionComponent<Props> = ({tweetReply}) => {
    const getTweetReplyNameMessage = (record) => {
        return NAME_CHOICES.filter(element => element.id === record.name)[0] ?? null;
    }

    return (
        <ShowGuesser {...tweetReply}>
            <FunctionField
                source="name"
                render={record => {return getTweetReplyNameMessage(record).name;}}
            />;
            <FieldGuesser source="message" style={{whiteSpace: 'pre-line'}}/>
        </ShowGuesser>
    );
}
