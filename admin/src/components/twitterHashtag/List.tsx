import {FunctionComponent} from "react";
import {TwitterHashtag} from "../../types/TwitterHashtag";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";

interface Props {
    twitterHashtags: TwitterHashtag[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({twitterHashtags}) => (
    <ListGuesser rowClick="show" {...twitterHashtags}>
        <FieldGuesser source="hashtag"/>
        <FieldGuesser source="active"/>
    </ListGuesser>
);
