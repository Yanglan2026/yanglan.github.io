import streamlit as st
from openai import OpenAI
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

# --- SETTINGS ---
# PASTE YOUR DEEPSEEK API KEY HERE
API_KEY = "sk-0f1e24bfacd24d67a2f8793e7a197f56" 

client = OpenAI(api_key=API_KEY, base_url="https://api.deepseek.com")

st.set_page_config(page_title="NPU Campus Assistant", page_icon="npu_logo.png")

# Logoyu sayfanın en üstüne ortalanmış olarak ekle (Genişliği 150 piksel)
col1, col2, col3 = st.columns([1, 2, 1])
with col2:
    st.image("npu_logo.png", use_container_width=True)

# Başlığı ve alt yazıyı tam ortalayan HTML/CSS kodu
st.markdown("""
    <h1 style='text-align: center; color: #1f77b4;'>NPU Campus Assistant</h1>
    <p style='text-align: center; font-size: 18px;'>AI-powered guide for new international students.</p>
""", unsafe_allow_html=True)

# --- MEMORY (SESSION STATE) ---
if "logged_in" not in st.session_state:
    st.session_state.logged_in = False

# --- LOGIN SCREEN ---
if not st.session_state.logged_in:
    with st.form("login_form"):
        st.subheader("System Login")
        name = st.text_input("Full Name (as in Passport)")
        id_number = st.text_input("Passport or Student ID Number", type="password")
        start_button = st.form_submit_button("Start Chat")
        
        if start_button:
            if name and id_number: 
                st.session_state.logged_in = True
                st.session_state.user_name = name
                st.rerun() 
            else:
                st.error("Please fill in all fields.")

# --- CHAT SCREEN ---
else:
    st.success(f"Welcome, {st.session_state.user_name}! You can ask your questions.")
    
    def load_knowledge_base():
        try:
            with open("bilgi.txt", "r", encoding="utf-8") as f:
                text = f.read()
            # Split into chunks starting from "Topic:"
            chunks = [p.strip() for p in text.split("Topic:") if p.strip()]
            return ["Topic: " + p for p in chunks]
        except FileNotFoundError:
            return ["Knowledge base file not found."]

    def get_answer(question):
        knowledge_chunks = load_knowledge_base()
        
        # If the knowledge base is empty or missing
        if not knowledge_chunks or len(knowledge_chunks) == 0:
             return "No information is registered in the system."

        vectorizer = TfidfVectorizer()
        # Convert knowledge chunks and the question into vectors
        vectors = vectorizer.fit_transform(knowledge_chunks + [question])
        similarities = cosine_similarity(vectors[-1], vectors[:-1])
        best_index = similarities.argmax()
        
        # If the question is not related to the knowledge base (similarity is low)
        if similarities[0][best_index] < 0.01:
            relevant_info = "No specific information found in the database for this exact question."
        else:
            relevant_info = knowledge_chunks[best_index]
        
        system_prompt = f"""
        You are the NPU Campus Assistant, a highly intelligent and polite AI for new international students.
        
        YOUR RULES:
        1. LANGUAGE: You MUST reply in the exact same language the user asked the question in (e.g., if they ask in Turkish, reply in Turkish; if Russian, reply in Russian).
        2. INFORMATION SOURCE: The 'INFORMATION' provided below may contain English, Turkish, and Russian text all together. Read them all, understand the core meaning, and generate a natural, helpful answer in the user's language.
        3. NO HALLUCINATION: Strictly use the facts from the provided INFORMATION. DO NOT invent addresses, prices, or rules. 
        4. HELPFULNESS: If the information includes step-by-step directions, convey them clearly. If the user asks something not in the database (or if the info says "No specific information found"), politely apologize and suggest alternative topics you CAN help with (like SIM cards, ICBC bank, or insurance). Never just say "I don't know" without offering help.
        
        INFORMATION TO USE:
        {relevant_info}
        """
        
        response = client.chat.completions.create(
            model="deepseek-chat",
            messages=[{"role": "system", "content": system_prompt}, {"role": "user", "content": question}],
            temperature=0.1
        )
        return response.choices[0].message.content

    if "messages" not in st.session_state:
        st.session_state.messages = []

    for message in st.session_state.messages:
        with st.chat_message(message["role"]):
            st.markdown(message["content"])

    if question := st.chat_input("Type your question here..."):
        st.session_state.messages.append({"role": "user", "content": question})
        with st.chat_message("user"):
            st.markdown(question)

        with st.spinner("Thinking..."):
            answer = get_answer(question)
        
        st.session_state.messages.append({"role": "assistant", "content": answer})
        with st.chat_message("assistant"):
            st.markdown(answer)