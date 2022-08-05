import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {NAME_CHOICES} from "../../config/tweetReply.ts";

interface Props {
    tweetReplies: TweetReply[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({tweetReplies}) => {
    const getTweetReplyNameMessage = (record) => {
        return NAME_CHOICES.filter(element => element.id === record.name)[0] ?? null;
    }

    return (
        <ListGuesser rowClick="show" {...tweetReplies}>
            <FunctionField
                label="Utilité"
                render={record => {return getTweetReplyNameMessage(record).name;}}
            />;
            <FieldGuesser source="message"/>
        </ListGuesser>
    );
}
