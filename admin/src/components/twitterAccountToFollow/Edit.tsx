import {FunctionComponent} from "react";
import {TwitterAccountToFollow} from "../../types/TwitterAccountToFollow";
import {EditGuesser, FieldGuesser, InputGuesser} from "@api-platform/admin";

interface Props {
    twitterAccountToFollow: TwitterAccountToFollow;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({twitterAccountToFollow}) => (
    <EditGuesser {...twitterAccountToFollow}>
        <InputGuesser source="twitterAccountUsername" disabled />
        <InputGuesser source="twitterAccountName" disabled />
        <InputGuesser source="active" />
    </EditGuesser>
);
