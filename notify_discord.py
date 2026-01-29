#!/usr/bin/env python3
import json
import os
import subprocess
import sys

def main():
    if len(sys.argv) < 2:
        print("Usage: notify_discord.py <DISCORD_WEBHOOK_URL>")
        sys.exit(1)

    webhook_url = sys.argv[1]

    commit_message = os.getenv("CI_COMMIT_MESSAGE", "")
    git_tag = os.getenv("CI_COMMIT_TAG") or ""

    if "INFORMATIQUE" in commit_message:
        return

    # --- Règle 1 : no_notify ---
    if "no_notify" in commit_message:
        print("ℹ️ Tag no_notify détecté : notification ignorée.")
        sys.exit(0)

    # --- Règles Pull (priorité) ---
    if "needed_pull" in commit_message:
        pull_info = "🔴 Pull obligatoire"
        color = 15158332  # rouge
    elif "high_recommended_pull" in commit_message:
        pull_info = "🟡 Pull fortement recommandé"
        color = 16705372  # orange
    elif "recommended_pull" in commit_message:
        pull_info = "🔵 Pull recommandé"
        color = 3447003    # bleu
    else:
        pull_info = "🟢 Pull non obligatoire"
        color = 3066993   # vert


    pipeline_id = os.getenv("CI_PIPELINE_ID", "")
    project_path = os.getenv("CI_PROJECT_PATH", "")
    pipeline_url = os.getenv("CI_PIPELINE_URL", "")
    commit_tag = commit_message or "Aucun"

    public_repo = "https://github.com/firstruner/Firstruner_Framework_PHP"
    private_repo = "https://gitlab.com/firstruner/Firstruner_Framework_PHP"

    description_private = (
        f"[Voir le pipeline]({pipeline_url})\n"
        f"[Repo privé GitLab]({private_repo})\n"
        f"\n"
    )

    description = (
        f"✅ **Firstruner Framework PHP : Nouvelle version !**\n"
        f"{pull_info}\n\n"
        f"Pipeline **#{pipeline_id}** terminé\n"
        f"Projet : **{project_path}**\n"
        f"Tag : **{commit_tag}**\n"
        f"\n"
        f"{description_private if ('gitlab' in webhook_url) else ''}"
        f"[Repo public GitHub]({public_repo})\n\n"
        f"**Description du commit :**\n{clean_commit_message(commit_message)}"
    )

    payload = json.dumps({
        "username": "Botty via GitLab CI/CD",
        "embeds": [{
            "title": "Publication Firstruner Framework PHP",
            "description": description,
            "color": color
        }]
    })

    subprocess.run(
        [
            "curl", "-sS", "-X", "POST",
            "-H", "Content-Type: application/json",
            "-d", payload,
            webhook_url
        ],
        check=True
    )

    print("✅ Notification Discord envoyée avec succès.")

def clean_commit_message(message: str) -> str:
    rules = [
        "no_notify",
        "needed_pull",
        "high_recommended_pull",
        "recommended_pull",
    ]

    cleaned = message
    for rule in rules:
        cleaned = cleaned.replace(rule, "")

    # Nettoyage des espaces multiples et lignes vides
    cleaned = "\n".join(
        line.strip()
        for line in cleaned.splitlines()
        if line.strip()
    )

    return cleaned.strip()

if __name__ == "__main__":
    main()
