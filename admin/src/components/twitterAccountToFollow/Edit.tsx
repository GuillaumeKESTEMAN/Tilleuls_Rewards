import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {EditGuesser, InputGuesser} from "@api-platform/admin";

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

export const Edit: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <EditGuesser {...twitterAccountToFollow}>
        <InputGuesser source="username" disabled/>
        <InputGuesser source="name" disabled/>
        <InputGuesser source="active"/>
    </EditGuesser>
);
