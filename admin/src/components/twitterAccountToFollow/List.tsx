import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";

interface Props {
    twitterAccountToFollows: TwitterAccountToFollow[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({twitterAccountToFollows}) => (
    <ListGuesser rowClick="show" {...twitterAccountToFollows}>
        <FieldGuesser source="username"/>
        <FieldGuesser source="name"/>
        <FieldGuesser source="active"/>
    </ListGuesser>
);
