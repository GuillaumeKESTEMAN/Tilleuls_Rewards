import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {EditGuesser, InputGuesser} from "@api-platform/admin";

interface Props {
    reward: Reward;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({reward}) => {
    return (
        <EditGuesser {...reward}>
            <InputGuesser source="distributed"/>
        </EditGuesser>
    );
}
